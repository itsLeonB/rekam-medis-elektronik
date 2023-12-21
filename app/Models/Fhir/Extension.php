<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Extension extends FhirModel
{
    use HasFactory;

    public function extendable(): MorphTo
    {
        return $this->morphTo('extendable');
    }

    public function valueAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function valueAge(): MorphOne
    {
        return $this->morphOne(Age::class, 'ageable');
    }

    public function valueAnnotation(): MorphOne
    {
        return $this->morphOne(Annotation::class, 'annotationable');
    }

    public function valueAttachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function valueCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function valueCoding(): MorphOne
    {
        return $this->morphOne(Coding::class, 'codeable');
    }

    public function valueContactPoint(): MorphOne
    {
        return $this->morphOne(ContactPoint::class, 'contact_pointable');
    }

    public function valueCount(): MorphOne
    {
        return $this->morphOne(Count::class, 'countable');
    }

    public function valueDistance(): MorphOne
    {
        return $this->morphOne(Distance::class, 'distanceable');
    }

    public function valueDuration(): MorphOne
    {
        return $this->morphOne(Duration::class, 'durationable');
    }

    public function valueHumanName(): MorphOne
    {
        return $this->morphOne(HumanName::class, 'human_nameable');
    }

    public function valueIdentifier(): MorphOne
    {
        return $this->morphOne(Identifier::class, 'identifiable');
    }

    public function valueMoney(): MorphOne
    {
        return $this->morphOne(Money::class, 'moneyable');
    }

    public function valuePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function valueQuantity(): MorphOne
    {
        return $this->morphOne(Quantity::class, 'quantityable');
    }

    public function valueRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable');
    }

    public function valueRatio(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable');
    }

    public function valueSampledData(): MorphOne
    {
        return $this->morphOne(SampledData::class, 'sampleable');
    }

    public function valueSignature(): MorphOne
    {
        return $this->morphOne(Signature::class, 'signable');
    }

    public function valueTiming(): MorphOne
    {
        return $this->morphOne(Timing::class, 'timeable');
    }

    public function valueContactDetail(): MorphOne
    {
        return $this->morphOne(ContactDetail::class, 'contact_detailable');
    }

    public function valueContributor(): MorphOne
    {
        return $this->morphOne(Contributor::class, 'contributable');
    }

    public function valueDataRequirement(): MorphOne
    {
        return $this->morphOne(DataRequirement::class, 'data_requireable');
    }

    public function valueExpression(): MorphOne
    {
        return $this->morphOne(Expression::class, 'expressable');
    }

    public function valueParameterDefinition(): MorphOne
    {
        return $this->morphOne(ParameterDefinition::class, 'parameter_defineable');
    }

    public function valueRelatedArtifact(): MorphOne
    {
        return $this->morphOne(RelatedArtifact::class, 'related_artifactable');
    }

    public function valueTriggerDefinition(): MorphOne
    {
        return $this->morphOne(TriggerDefinition::class, 'trigger_defineable');
    }

    public function valueUsageContext(): MorphOne
    {
        return $this->morphOne(UsageContext::class, 'usage_contextable');
    }

    public function valueDosage(): MorphOne
    {
        return $this->morphOne(Dosage::class, 'dosageable');
    }

    public function valueMeta(): MorphOne
    {
        return $this->morphOne(Meta::class, 'metaable');
    }

    public function valueReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }
}
